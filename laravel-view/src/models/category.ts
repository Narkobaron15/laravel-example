import { object, string, number, mixed, InferType } from 'yup';
import { ERROR_MESSAGES, IApiImage, MAX_FILE_SIZE } from './common';

// single-file picture validations
const picTest = (value: any) => {
    if (value) {
        // Перевірка на тип обраного файлу - допустимий тип jpeg, png, gif
        const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
        return allowedTypes.includes(value.type);
    }
    // if null
    return true;
}, sizeTest = (value: any) => {
    if (!value || !value.length) return true; // attachment is optional
    return value[0].size <= MAX_FILE_SIZE; // 50 MB
};
const imgValidation = mixed()
    .test("fileType", ERROR_MESSAGES.NOT_A_PICTURE, picTest)
    .test("fileSize", ERROR_MESSAGES.FILE_TOO_LARGE, sizeTest);

// validation schemas for category create / update operations
export const categoryCreateSchema = object({
    id: number().nullable(),
    name: string()
        .min(5, ERROR_MESSAGES.TOO_SMALL)
        .max(200, ERROR_MESSAGES.TOO_LARGE)
        .required(ERROR_MESSAGES.REQUIRED),
    image: imgValidation.required(ERROR_MESSAGES.REQUIRED),
    description: string()
        .max(4000, ERROR_MESSAGES.TOO_LARGE)
        .required(ERROR_MESSAGES.REQUIRED),
});
// schema.shape is used to create a new schema based on the other one
export const categoryUpdateSchema = categoryCreateSchema.shape({
    image: imgValidation.nullable(),
});

/*
 * Types in typescript exist in 'type' scope, just as interfaces
 * (Classes are both in 'type' and 'value' scope, 
 * so they are both left and right values)
 */
export type ICategoryCreateModel = InferType<typeof categoryUpdateSchema>;

// Category interfaces
export interface ICategoryReadModel {
    get id(): number;
    get name(): string;
    get description(): string;
    get picture(): IApiImage;
}

// Sample item 
export const initCategory: ICategoryCreateModel = {
    id: null,
    name: '',
    image: null,
    description: '',
};