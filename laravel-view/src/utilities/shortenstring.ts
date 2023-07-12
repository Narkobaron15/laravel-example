export default function ShortenString(str: string, maxLength: number): string {
    return str.length <= maxLength ? str : str.substring(0, maxLength - 3) + '...';
}