import React from "react";
import { useNavigate } from "react-router-dom";

import { ICategoryCreateItem, categoryCreateSchema, initCategory } from "../../../models/category";
import http_common from "../../../http_common";
import { callErrorToast } from "../errortoast";
import RenderCUForm from "../renderform";

export default function CreateCategory() {
    // used for redirecting after successful form submit
    const navigate = useNavigate();

    // the logic of submit button on formik form
    const formikSubmit = async (val: ICategoryCreateItem) => {
        const validatedVal = await categoryCreateSchema.validate(val);
        await http_common.post("api/categories/create", validatedVal,
            { // http request params
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
            .then(() => navigate("/"))
            .catch(callErrorToast);
    }

    return <RenderCUForm
        initialVals={initCategory}
        validationSchema={categoryCreateSchema}
        updating={false}
        formikSubmit={formikSubmit} />;
}