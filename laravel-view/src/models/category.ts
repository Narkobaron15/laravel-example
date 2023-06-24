export default interface ICategoryItem {
    get id(): number | undefined;
    get name(): string;
    get image(): string;
    get description(): string;
}