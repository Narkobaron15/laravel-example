import { object, string, number, InferType } from 'yup';

export const categorySchema = object({
    id: number().nullable(),
    name: string().min(3, "Too short").max(100, 'Too long').required(),
    image: string().max(400, 'Shorten the path').required(),
    description: string().max(500, 'Too long').nullable(),
});

type ICategoryItem = InferType<typeof categorySchema>;
export default ICategoryItem;