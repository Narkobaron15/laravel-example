import React from "react"
import { useNavigate } from "react-router-dom";
import { Formik, Form, Field } from "formik";

import ICategoryItem, { categorySchema } from "../../../models/category";
import http_common from "../../../http_common";

const emptyCategory: ICategoryItem = {
    id: undefined,
    name: '',
    image: '',
    description: '',
}

export default function CreateCategory() {
    // used for redirecting after successful form submit
    const navigate = useNavigate();

    // the logic of submit button on formik form
    const formikSubmit = async (val: ICategoryItem) => {
        try {
            const validatedVal = await categorySchema.validate(val);
            await http_common.post("api/categories/create", validatedVal);
            navigate("/all");
        }
        catch (err: any) {
            console.error('Server side error. Details: ' + err.message);
        }
    }

    // https://formik.org/docs/guides/validation

    // Formik component syntax
    return (
        <Formik initialValues={emptyCategory} validationSchema={categorySchema} onSubmit={formikSubmit}>
            {({ errors, touched }) => (
                <Form className="mx-auto">
                    <div className="form-group">
                        <div className="md:w-1/3">
                            <label htmlFor="name">Назва</label>
                        </div>
                        <div className="md:w-2/3">
                            <Field
                                id="name"
                                name="name"
                                type="text"
                                placeholder="Введіть назву..."
                            />
                        </div>
                    </div>
                    {/* 
                      * if any errors were registered,
                      * the error message will be shown
                      */}
                    {
                        errors.name && touched.name
                            ? <div className="error-text">{errors.name}</div>
                            : null
                    }
                    <div className="form-group">
                        <div className="md:w-1/3">
                            <label htmlFor="image">Зображення</label>
                        </div>
                        <div className="md:w-2/3">
                            <Field
                                id="image"
                                name="image"
                                type="text"
                                placeholder="Введіть адресу зображення..."
                            />
                        </div>
                    </div>
                    {
                        errors.image && touched.image
                            ? <div className="error-text">{errors.image}</div>
                            : null
                    }
                    <div className="form-group">
                        <div className="md:w-1/3">
                            <label htmlFor="description">Опис</label>
                        </div>
                        <div className="md:w-2/3">
                            <Field
                                id="description"
                                name="description"
                                type="text"
                                placeholder="Введіть опис..."
                            />
                        </div>
                    </div>
                    {
                        errors.description && touched.description
                            ? <div className="error-text">{errors.description}</div>
                            : null
                    }
                    <div className="flex justify-center">
                        <button type="submit">Додати</button>
                    </div>
                </Form>
            )}
        </Formik>
    );
}