import IProcess from "./IProcess.js";
import $ from "../jquery.js";
import Api from "../Api/Api.js";
import {MessageType} from "../Message/Message.js";

/**
 * @enum {string}
 */
const DeleteProjectEvents = {
    Complete: "process.deleteproject.complete",
    NewRequestArrived: "process.deleteproject.newrequest"
};

class DeleteProject extends IProcess
{
    /**
     * @type {Set<number>}
     */
    pool = new Set();
    /**
     * @type {number[]}
     */
    successful = [];
    /**
     * @type {number}
     */
    identifier = 0;
    /**
     * @type {number|null}
     */
    debounceForCompletion = null;

    registerHandlers()
    {
        $("[data-pamut-action='delete']").on("click", async event => this.trigger(event));
    }

    async handle(event)
    {
        const button = $(event.target);
        const form = button.closest("form");
        if (!button.length || !form.length)
        {
            return;
        }

        const id = Number(form.find("input[name='id']").val());
        if (!id || id < 0)
        {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const confirmMessage = form.attr("data-confirm");
        if (confirmMessage && !window.confirm(confirmMessage))
        {
            return;
        }

        window.clearTimeout(this.debounceForCompletion);
        window.dispatchEvent(new CustomEvent(DeleteProjectEvents.NewRequestArrived));

        const connectionId = this.identifier++;

        const container = form.closest("[data-pamut-disable]");
        this.disableCurrentElement(container);

        try
        {
            this.pool.add(connectionId);
            const result = await Api.deleteProject(id);

            this.message.add(result.message, MessageType.Success);
            await this.destroyElement(container);

            this.successful.push(connectionId);
        }
        catch (/** @type {Object} */ e)
        {
            if (e.responseJSON)
            {
                this.message.add(e.responseJSON.error.message, MessageType.Error);
            }
            else
            {
                this.message.add("Ismeretlen hiba lépett fel.", MessageType.Error);
            }

            this.enableCurrentElement(container);
        }

        this.pool.delete(connectionId);
        this.debounceForCompletion = window.setTimeout(() => {
            if (this.pool.size === 0 && this.successful.length > 0)
            {
                this.successful = [];
                window.dispatchEvent(new CustomEvent(DeleteProjectEvents.Complete));
            }
        }, 150);
    }

    /**
     * @param element
     * @param {number} debounce
     */
    disableCurrentElement(element, debounce = 100)
    {
        window.clearTimeout(element.data("debounce"));
        const timeout = window.setTimeout(() => {
            element.addClass("pamut-loading");
            element.find("button, a")
                .attr("disabled", "disabled")
                .addClass("disabled")
            ;
        }, debounce);
        element.data("debounce", timeout);
    }

    enableCurrentElement(element)
    {
        window.clearTimeout(element.data("debounce"));
        element.removeClass("pamut-loading");
        element.find("button, a")
            .removeAttr("disabled")
            .removeClass("disabled")
        ;
    }

    /**
     * @param element
     * @param {number} animationLength
     * @return Promise<void>
     */
    async destroyElement(element, animationLength = 250)
    {
        return new Promise(resolve => {
            element.addClass("pamut-loading overflow-hidden");
            element.animate({
                left: "100vw",
                top: "-200px",
                height: 0,
                opacity: 0,
                paddingTop: 0,
                paddingBottom: 0,
                borderWidth: 0
            }, animationLength, "swing", () => {
                window.setTimeout(() => {
                    element.hide();
                    resolve();
                }, animationLength);
            });
        });
    }
}

export { DeleteProjectEvents };
export default DeleteProject;