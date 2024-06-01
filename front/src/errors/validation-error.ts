export default class ValidationError extends Error
{
    protected errorFields : Array<any> = [];
    public constructor(message: string, errorFields: Array<any>) {
        super(message);
        this.errorFields = errorFields;
    }

    public getErrorFields()
    {
        return this.errorFields;
    }
}