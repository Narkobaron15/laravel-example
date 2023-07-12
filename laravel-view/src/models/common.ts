export const ERROR_MESSAGES = {
    REQUIRED: "Це поле є обов'язковим",
    IMG_REQUIRED: "Загрузіть картинку",
    TOO_SMALL: "Занадто коротке значення",
    TOO_LARGE: "Занадто довге значення",
    NOT_A_PICTURE: "Файли крім картинок не дозволені",
    FILE_TOO_LARGE: "Файл занадто великий",
    LESS_THAN_ZERO: "Значення не може бути менше нуля",
    NUMERIC: "Значення має бути числом",
    INTEGER: "Значення має бути цілим числом",
    CATEGORY_NOT_FOUND: "Виберіть категорію"
}

export const MAX_FILE_SIZE = 50_000_000; // 50 MB

export interface IApiImage {
    get priority(): number | null | undefined
    get xs(): string,
    get sm(): string,
    get md(): string,
    get lg(): string,
    get xl(): string,
}