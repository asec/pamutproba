class ProcessManager
{
    /**
     * @type {Array<string, IProcess>}
     */
    #processes = {};

    /**
     * @param {string} name
     * @param {IProcess} process
     */
    add(name, process)
    {
        this.#processes[name] = process;
    }

    /**
     * @param {string} name
     * @return {IProcess}
     */
    get(name)
    {
        return this.#processes[name];
    }

    registerHandlers()
    {
        for (let i in this.#processes)
        {
            const process = this.#processes[i];
            process.registerHandlers();
        }
    }
}

export default ProcessManager;