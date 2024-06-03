import ValidationError from '@/errors/validation-error';

export const setFieldErrors = (targetActions : any, error : any) => {
  if (error instanceof ValidationError) {
    targetActions.setErrors(error.getErrorFields());
  }
}