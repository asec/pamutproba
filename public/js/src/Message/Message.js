import $ from "../jquery";

/**
 * @enum {string}
 */
const MessageType = {
    Success: "text-bg-success",
    Error: "text-bg-danger"
}

class Message
{
    element = null;
    toast = null;

    constructor(id)
    {
        this.createDomElements(id);
    }

    /**
     * @param {string} id
     */
    createDomElements(id)
    {
        const selector = `#${id}`;
        const toastContainerSelector = `#toast-container`;
        if ($(selector).length > 0)
        {
            return;
        }

        if ($(toastContainerSelector).length === 0)
        {
            const element = $(document.createElement("div"));
            element.html('<div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container"></div>');
            $("body").append(element);
        }

        const element = $(toastContainerSelector);
        element.append(`
            <div class="toast fade align-items-center text-bg-success border-0" id="${id}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body"></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Bezár"></button>
                </div>
            </div>
        `);

        this.element = $(selector);
        this.toast = window.bootstrap.Toast.getOrCreateInstance(this.element.get(0));
    }

    /**
     * @param {string} message
     * @param {MessageType} type
     */
    add(message, type = MessageType.Success)
    {
        if (this.toast.isShown())
        {
            this.toast.hide();
            this.element.one("hidden.bs.toast", () => {
                this.#changeContent(message, type);
                setTimeout(() => this.toast.show());
            });
            return;
        }

        this.#changeContent(message, type);
        this.toast.show();
    }

    /**
     * @param {string} message
     * @param {MessageType} type
     */
    #changeContent(message, type)
    {
        for (let i in MessageType)
        {
            this.element.removeClass(MessageType[i]);
        }
        this.element.addClass(type);
        this.element.find(".toast-body").html(message);
    }
}

export { MessageType };
export default Message;