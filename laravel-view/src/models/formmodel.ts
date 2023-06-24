export default interface IFormModel {
    get method(): string,
    get action(): string,
    get className(): string,
    get submitBtnText(): string,
    get returnUrl(): string,
    get rows(): IFormRowModel[],
}

export interface IFormRowModel {
    get htmlId(): string,
    get labelInnerHtml(): string,
    get inputType(): string,
    get inputPlaceholder(): string | undefined,
}