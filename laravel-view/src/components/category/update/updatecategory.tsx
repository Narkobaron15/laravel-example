import React, { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";

import { ICategoryCreateModel, categoryUpdateSchema, initCategory } from "../../../models/category";
import http_common from "../../../http_common";
import { callErrorToast } from "../../errortoast";
import RenderCategoryCUForm from "../rendercategoryform";


export default function UpdateCategory() {
    // extracting URL parameters by using the 'useParams' hook
    const { id } = useParams();

    // used for redirecting after successful form submit
    // or when the category is not found 
    const navigate = useNavigate();

    // grabbing current category data to fill in the updating form
    const [initialVals, setInitialVals] = useState<ICategoryCreateModel>(initCategory);
    const [initimg, setInitialimg] = useState('');
    useEffect(() => {
        http_common.get(`/api/categories/${id}`)
            .then(response => {
                response.data.image = null;
                setInitialVals(response.data);
                setInitialimg(response.data.picture_s);
            })
            .catch(error => {
                callErrorToast(error);
                navigate(`/categories`);
            });
    }, [id]);

    // the logic of submit button on formik form
    const formikSubmit = async (val: ICategoryCreateModel) => {
        const validatedVal = await categoryUpdateSchema.validate(val);
        // posting request to update the category onto edit path
        await http_common
            .post(`api/categories/edit/${id}`, validatedVal,
                { // http request params
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
            // and redirecting if everything is fine
            .then(() => navigate("/categories"))
            // if not, a toast message is shown
            .catch(callErrorToast);
    }

    // common form rendering object for creating and updating categories
    return <RenderCategoryCUForm
        initialVals={initialVals}
        validationSchema={categoryUpdateSchema}
        formikSubmit={formikSubmit}
        updating={true}
        initImg={initimg} />;
}