export default class ValidationError extends Error
{
    protected errorFields : Array<any> = [];
    public constructor(message: string, errorFields: Array<any>) {
        super(message);
        // Set the prototype explicitly.
        Object.setPrototypeOf(this, ValidationError.prototype);
        this.name = 'ValidationError';
        this.errorFields = errorFields;
    }

    public getErrorFields()
    {
        return this.errorFields;
    }
}