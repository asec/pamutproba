import $ from "../jquery";

class jqXHR extends Promise
{
    abort()
    {}
}

class Api
{
    /**
     * @param {string} endpoint
     * @return {string}
     */
    static endpointUrl(endpoint = "")
    {
        return window.app.apiEntryPoint + endpoint;
    }

    /**
     * @param {number} id
     */
    static async deleteProject(id)
    {
        return $.ajax({
            dataType: "json",
            method: "DELETE",
            url: this.endpointUrl("api/projekt"),
            data: {
                id
            }
        });
    }

    static async updateView()
    {
        const params = new URLSearchParams(window.location.search);
        let data = {};
        ["status", "page"].map(key => {
            if (params.has(key))
            {
                data[key] = params.get(key);
            }
        });

        return $.ajax({
            dataType: "html",
            method: "GET",
            url: this.endpointUrl(),
            headers: {
                "Pamut-Ajax-Partial": "true"
            },
            data
        });
    }

    /**
     * @param {number} ms
     */
    static async wait(ms)
    {
        return $.ajax({
            dataType: "json",
            method: "GET",
            url: this.endpointUrl("dev/wait"),
            data: {
                ms: ms || null
            }
        });
    }
}

export { jqXHR };
export default Api;