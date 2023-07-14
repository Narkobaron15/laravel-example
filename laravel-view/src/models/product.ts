import { object, number, string, InferType, array } from 'yup';
import { ERROR_MESSAGES, IApiImage, MAX_FILE_SIZE } from './common';

// multi-file picture validations
const picTest = (value?: any[] | null | undefined) => {
    if (value === null || value === undefined || value.length === 0)
        return true; // attachment is optional

    // File type check (allowed types - jpeg, png, gif)
    const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
    for (const file of value) {
        if (!(file instanceof File) || !allowedTypes.includes(file.type)) {
            return false;
        }
    }

    return true;
}, sizeTest = (value?: any[] | null | undefined) => {
    if (value === null || value === undefined || value.length === 0)
        return true; // attachment is optional

    // if attached, check every file
    for (const file of value) {
        if (!(file instanceof File) || file.size > MAX_FILE_SIZE)
            return false;
    }

    return true;
}, img_required = (value?: any[] | null | undefined) => {
    return (value instanceof FileList || value instanceof Array) && value.length > 0;
};

const imgValidation = array()
    .test("fileType", ERROR_MESSAGES.NOT_A_PICTURE, picTest)
    .test("fileSize", ERROR_MESSAGES.FILE_TOO_LARGE, sizeTest);

// validation schemas for product create / update operations
export const productCreateSchema = object({
    id: number().nullable(),
    name: string()
        .min(5, ERROR_MESSAGES.TOO_SMALL)
        .max(200, ERROR_MESSAGES.TOO_LARGE)
        .required(ERROR_MESSAGES.REQUIRED),
    price: number()
        .min(0, ERROR_MESSAGES.LESS_THAN_ZERO)
        .required(ERROR_MESSAGES.REQUIRED),
    category_id: number()
        .integer(ERROR_MESSAGES.INTEGER)
        .min(1, ERROR_MESSAGES.CATEGORY_NOT_FOUND)
        .required(ERROR_MESSAGES.REQUIRED),
    description: string()
        .max(4000, ERROR_MESSAGES.TOO_LARGE)
        .required(ERROR_MESSAGES.REQUIRED),
    images: imgValidation/*.required(ERROR_MESSAGES.IMG_REQUIRED)*/
        .test("Required", ERROR_MESSAGES.IMG_REQUIRED, img_required),
});
// schema.shape is used to create a new schema based on the other one
export const productUpdateSchema = productCreateSchema.shape({
    images: imgValidation.nullable(),
    remove_images: array().of(number().min(0).integer().required()).nullable(),
});

export type IProductCreateModel = InferType<typeof productCreateSchema>;
export type IProductUpdateModel = InferType<typeof productUpdateSchema>;

export interface IProductReadModel {
    get id(): number;
    get name(): string;
    get price(): number;
    get description(): string;
    get category_id(): number;
    get category_name(): string;
    get primary_image(): IApiImage;
    get images(): IApiImage[],
}

export const emptyProduct: IProductCreateModel = {
    id: null,
    name: "",
    price: 0,
    category_id: 0,
    description: "",
    images: [],
};