import { object, string, number, InferType } from 'yup';

const ERROR_MESSAGES = {
    REQUIRED: "Це поле є обов'язковим",
    TOO_SMALL: "Занадто коротке значення",
    TOO_LARGE: "Занадто довге значення",
}

export const categorySchema = object({
    id: number().nullable(),

    name: string()
        .min(3, ERROR_MESSAGES.TOO_SMALL)
        .max(100, ERROR_MESSAGES.TOO_LARGE)
        .required(ERROR_MESSAGES.REQUIRED),
    image: string()
        .max(400, ERROR_MESSAGES.TOO_LARGE)
        .required(ERROR_MESSAGES.REQUIRED),
    description: string()
        .max(500, ERROR_MESSAGES.TOO_LARGE)
        .required(ERROR_MESSAGES.REQUIRED),
});

/*
 * Types in typescript exist in 'type' scope, just as interfaces
 * (Classes are both in 'type' and 'value' scope, 
 * so they are both left and right values)
 * 
 * Currently no implementation needed
 */
type ICategoryItem = InferType<typeof categorySchema>;
export default ICategoryItem;