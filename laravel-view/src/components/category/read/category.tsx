import ICategoryItem from "../../../models/category"

type CategoryArgs = {
    params: ICategoryItem,
}

export default function Category({ params }: CategoryArgs) {
    return (
        <tr>
            <th scope="row">{params.id}</th>
            <td>{params.name}</td>
            <td>{params.image}</td>
            <td>{params.description}</td>
        </tr>
    );
}