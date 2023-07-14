export default function PriceToString(price?: number): string {
    return price !== undefined
        ? (price <= 0 ? "Безкоштовно" : `$` + Number(price).toFixed(2))
        : ``;
}