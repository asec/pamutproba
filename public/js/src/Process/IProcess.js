import MustImplementException from "../Exception/MustImplementException";
import {MessageType} from "../Message/Message";

class IProcess
{
    /**
     * @type {Message|null}
     */
    #message = null;

    /**
     * @return {Message|null}
     */
    get message()
    {
        return this.#message;
    }

    /**
     * @param {Message} message
     * @param args
     */
    constructor(message, ...args)
    {
        this.#message = message;
    }

    /**
     * @abstract
     */
    registerHandlers()
    {
        throw MustImplementException.with(this.constructor.name, "registerHandlers");
    }

    /**
     * @param args
     */
    async trigger(...args)
    {
        try
        {
            return await this.handle(...args);
        }
        catch (/** @type {Error} */e)
        {
            this.#message.add(e.message, MessageType.Error);
        }
    }

    /**
     * @abstract
     * @param args
     * @return {Promise<void>}
     */
    async handle(...args)
    {
        throw MustImplementException.with(this.constructor.name, "trigger");
    }

    /**
     * @param {Object} message
     */
    notify(message)
    {}
}

export default IProcess;