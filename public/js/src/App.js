import Message, {MessageType} from "./Message/Message.js";
import ProcessManager from "./Process/ProcessManager.js";
import DeleteProject, {DeleteProjectEvents} from "./Process/DeleteProject.js";
import RefreshView, {RefreshViewEvents} from "./Process/RefreshView.js";

class App
{
    /**
     * @type {string}
     */
    static #refreshTarget = "#ajax-refresh-target";
    /**
     * @type {Message|null}
     */
    static #message = null;
    /**
     * @type {ProcessManager|null}
     */
    static #processManager = null;

    static init()
    {
        this.#message = new Message("ajax-message");

        try
        {
            this.#processManager = new ProcessManager();
            this.#processManager.add("delete", new DeleteProject(this.#message));
            this.#processManager.add("refresh", new RefreshView(this.#message, this.#refreshTarget));

            this.#processManager.registerHandlers();

            window.addEventListener(DeleteProjectEvents.Complete, async () => {
                await this.#processManager.get("refresh").trigger();
            });
            window.addEventListener(DeleteProjectEvents.NewRequestArrived, () => {
                window.dispatchEvent(new CustomEvent(RefreshViewEvents.Abort));
            });
            window.addEventListener(RefreshViewEvents.Complete, () => {
                this.#processManager.get("delete").registerHandlers();
            });
        }
        catch (/** @type Error */ e)
        {
            this.#message.add(e.message, MessageType.Error);
        }
    }
}

export default App;