import React from "react";
import { useNavigate } from "react-router-dom";
import { Formik, Form, Field, FormikErrors, FormikTouched } from "formik";

import ICategoryItem, { categorySchema } from "../../../models/category";
import http_common from "../../../http_common";

const emptyCategory: ICategoryItem = {
    id: undefined,
    name: '',
    image: '',
    description: '',
};

// for error displaying
const getErrorComponents = (
    errors: FormikErrors<ICategoryItem>,
    touched: FormikTouched<ICategoryItem>,
    field: keyof ICategoryItem /* "id" | "name" | "image" | "description" */) => {
    return errors[field] && touched[field]
        ? <div className="error-text">{errors[field]}</div>
        : null;
};

export default function CreateCategory() {
    // used for redirecting after successful form submit
    const navigate = useNavigate();

    // the logic of submit button on formik form
    const formikSubmit = async (val: ICategoryItem) => {
        const validatedVal = await categorySchema.validate(val);

        await http_common.post("api/categories/create", validatedVal)
            .catch(err => console.error('Server side error. Details: ' + err.message));

        navigate("/all");
    }

    // Formik component syntax
    return (
        // https://formik.org/docs/guides/validation
        <Formik initialValues={emptyCategory} validationSchema={categorySchema} onSubmit={formikSubmit}>
            {({ errors, touched }) => (
                <Form className="mx-auto">
                    <div className="form-group">
                        <div className="md:w-2/12">
                            <label htmlFor="name">Назва</label>
                        </div>
                        <div className="md:w-10/12">
                            <Field
                                id="name"
                                name="name"
                                type="text"
                                placeholder="Введіть назву..."
                            />
                        </div>
                    </div>
                    {
                        /* 
                         * if any errors were registered,
                         * the error message will be shown
                         */
                        getErrorComponents(errors, touched, "name")
                    }
                    <div className="form-group">
                        <div className="md:w-2/12">
                            <label htmlFor="image">Зображення</label>
                        </div>
                        <div className="md:w-10/12">
                            {/* not address, but the input type image */}
                            <Field
                                id="image"
                                name="image"
                                type="text"
                                placeholder="Введіть адресу зображення..."
                            />
                        </div>
                    </div>
                    {getErrorComponents(errors, touched, "image")}
                    <div className="form-group">
                        <div className="md:w-2/12">
                            <label htmlFor="description">Опис</label>
                        </div>
                        <div className="md:w-10/12">
                            <Field
                                id="description"
                                name="description"
                                type="text"
                                placeholder="Введіть опис..."
                            />
                        </div>
                    </div>
                    {getErrorComponents(errors, touched, "description")}
                    <div className="flex justify-center">
                        <button type="submit">Додати</button>
                    </div>
                </Form>
            )}
        </Formik>
    );
}