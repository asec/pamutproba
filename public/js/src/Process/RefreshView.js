import IProcess from "./IProcess.js";
import $ from "../jquery.js";
import Api from "../Api/Api.js";
import {MessageType} from "../Message/Message.js";

/**
 * @enum {string}
 */
const RefreshViewEvents = {
    Go: "process.refreshview.go",
    Complete: "process.refreshview.complete",
    Abort: "process.refreshview.abort"
};

class RefreshView extends IProcess
{
    /**
     * @type {string}
     */
    #refreshTarget;
    running = false;
    aborted = false;

    constructor(message, refreshTarget)
    {
        super(message);
        this.#refreshTarget = refreshTarget;
    }
    registerHandlers()
    {
        window.addEventListener(RefreshViewEvents.Go, () => this.trigger());
        window.addEventListener(RefreshViewEvents.Abort, () => this.abort());
    }

    async handle(...args)
    {
        this.running = true;
        try
        {
            const html = await Api.updateView();
            if (this.aborted)
            {
                this.aborted = false;
                this.running = false;
                return;
            }
            $(this.#refreshTarget).replaceWith(html);
            window.dispatchEvent(new CustomEvent(RefreshViewEvents.Complete));
        }
        catch (/** @type {Error|Object} */ e)
        {
            if (e.message)
            {
                this.message.add(e.message, MessageType.Error);
            }
            else
            {
                this.message.add("Ismeretlen hiba történt.", MessageType.Error);
            }
        }

        this.aborted = false;
        this.running = false;
    }

    abort()
    {
        if (this.running)
        {
            this.aborted = true;
        }
    }
}

export { RefreshViewEvents };
export default RefreshView;