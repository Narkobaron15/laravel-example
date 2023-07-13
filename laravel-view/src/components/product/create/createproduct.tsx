import React from "react";
import { useNavigate } from "react-router-dom";

import http_common from "../../../http_common";
import { callErrorToast } from "../../errortoast";
import RenderProductCUForm from "../renderproductform";
import { IProductUpdateModel, emptyProduct, productCreateSchema } from "../../../models/product";
import { ICategoryReadModel } from "../../../models/category";

export default function CreateProduct() {
    // used for redirecting after successful form submit
    const navigate = useNavigate();

    const [categories, setCategories] = React.useState<ICategoryReadModel[]>([]);
    React.useEffect(() => {
        http_common.get(`/api/categories`)
            .then(r => {
                setCategories(r.data);
                emptyProduct.category_id = r.data[0].id;
            })
            .catch(e => {
                callErrorToast(e);
                navigate(`/products`);
            });
    }, [navigate])

    // the logic of submit button on formik form
    const formikSubmit = async (val: IProductUpdateModel) => {
        if (categories.filter(c => c.id === val.category_id).length === 0) {
            callErrorToast(new Error("Wrong category, please select from given options or refresh the page."));
            return;
        }

        const validatedVal: any = await productCreateSchema.validate(val);

        // php accepts multiple files if square brackets are used in the parameter name
        validatedVal["images[]"] = validatedVal.images;
        delete validatedVal.images;

        // posting request to create the category onto creation path
        await http_common.post("/api/products/create", validatedVal,
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
        initialVals={emptyProduct}
        validationSchema={productCreateSchema}
        updating={false}
        formikSubmit={formikSubmit} />;
}