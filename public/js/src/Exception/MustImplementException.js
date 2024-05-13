class MustImplementException extends Error
{
    static with(className, methodName)
    {
        return new MustImplementException(`The following method needs to be implemented ${className}::${methodName}`);
    }
}

export default MustImplementException;