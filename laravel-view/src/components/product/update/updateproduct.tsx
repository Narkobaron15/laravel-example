import React from "react";
import { useNavigate, useParams } from "react-router-dom";

import http_common from "../../../http_common";
import { callErrorToast } from "../../errortoast";
import RenderProductCUForm from "../renderproductform";
import { IProductCreateModel, emptyProduct, productUpdateSchema } from "../../../models/product";
import { ICategoryReadModel } from "../../../models/category";

export default function UpdateProduct() {
    // used for redirecting after successful form submit
    const navigate = useNavigate();

    // extract parameters
    const { id } = useParams();

    const [categories, setCategories] = React.useState<ICategoryReadModel[]>([]);
    const [currentProduct, setCurrentProduct] = React.useState<IProductCreateModel>(emptyProduct);
    React.useEffect(() => {
        const catchFn = (e: any) => {
            callErrorToast(e);
            navigate(`/products`);
        };

        http_common.get(`/api/categories`)
            .then(r => setCategories(r.data))
            .catch(catchFn);

        http_common.get(`/api/products/${id}`)
            .then(r => {
                delete r.data.images;
                setCurrentProduct(r.data);
            })
            .catch(catchFn);

    }, [navigate, id])

    // the logic of submit button on formik form
    const formikSubmit = async (val: IProductCreateModel) => {
        // check if no inspector manipulations were performed
        // cast needed because of using the select html item
        if (categories.filter(c => c.id === Number(val.category_id)).length === 0) {
            callErrorToast(new Error("Wrong category, please select from given options or refresh the page."));
            return;
        }

        const validatedVal: any = await productUpdateSchema.validate(val);

        // php accepts multiple files if square brackets are used in the parameter name
        validatedVal["images[]"] = validatedVal.images;
        delete validatedVal.images;

        // posting request to create the category onto creation path
        await http_common.post(`api/products/edit/${id}`, validatedVal,
            { // http request params
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
            .then(() => navigate("/products"))
            .catch(callErrorToast);
    }

    // common form rendering object for creating and updating products
    return <RenderProductCUForm
        categories={categories}
        initialVals={currentProduct}
        validationSchema={productUpdateSchema}
        updating={true}
        formikSubmit={formikSubmit} />;
}