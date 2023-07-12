import React from "react";
import { useNavigate } from "react-router-dom";

import { ICategoryCreateModel, categoryCreateSchema, initCategory } from "../../../models/category";
import http_common from "../../../http_common";
import { callErrorToast } from "../../errortoast";
import RenderCategoryCUForm from "../rendercategoryform";

export default function CreateCategory() {
    // used for redirecting after successful form submit
    const navigate = useNavigate();

    // the logic of submit button on formik form
    const formikSubmit = async (val: ICategoryCreateModel) => {
        const validatedVal = await categoryCreateSchema.validate(val);
        // posting request to create the category onto creation path
        await http_common.post("api/categories/create", validatedVal,
            { // http request params
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
            .then(() => navigate("/categories"))
            .catch(callErrorToast);
    }

    // common form rendering object for creating and updating categories
    return <RenderCategoryCUForm
        initialVals={initCategory}
        validationSchema={categoryCreateSchema}
        updating={false}
        formikSubmit={formikSubmit} />;
}