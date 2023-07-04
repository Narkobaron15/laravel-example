import { object, string, number, mixed, InferType } from 'yup';

const ERROR_MESSAGES = {
    REQUIRED: "Це поле є обов'язковим",
    TOO_SMALL: "Занадто коротке значення",
    TOO_LARGE: "Занадто довге значення",
    NOT_A_PICTURE: "Це не картинка",
    FILE_TOO_LARGE: "Файл занадто великий",
}

// picture validations
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
    return value[0].size <= 50_000_000; // 50 MB
};
const imgValidation = mixed()
    .test("fileType", ERROR_MESSAGES.NOT_A_PICTURE, picTest)
    .test("fileSize", ERROR_MESSAGES.FILE_TOO_LARGE, sizeTest);

// validation schemas for category create / update operations
export const categoryCreateSchema = object({
    id: number().nullable(),
    name: string()
        .min(3, ERROR_MESSAGES.TOO_SMALL)
        .max(100, ERROR_MESSAGES.TOO_LARGE)
        .required(ERROR_MESSAGES.REQUIRED),
    image: imgValidation.required(ERROR_MESSAGES.REQUIRED),
    description: string()
        .max(500, ERROR_MESSAGES.TOO_LARGE)
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
export type ICategoryCreateItem = InferType<typeof categoryUpdateSchema>;

// Category interfaces
export interface ICategoryReadItem {
    get id(): number | null | undefined;
    get name(): string;
    get image(): string;
    get description(): string;

    get picture_xs(): string;
    get picture_s(): string;
    get picture_m(): string;
    get picture_l(): string;
    get picture_xl(): string;
}

// Sample item 
export const initCategory: ICategoryCreateItem = {
    id: undefined,
    name: '',
    image: undefined,
    description: '',
};