import React from "react";

import { Formik, Form, Field, FormikErrors, FormikTouched } from "formik";
import { InferType, ObjectSchema } from "yup";
import { ICategoryCreateModel, categoryUpdateSchema } from "../../models/category";

const defaultPic = "https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1361&q=80";

// for error displaying
const getErrorComponents = (
    errors: FormikErrors<ICategoryCreateModel>,
    touched: FormikTouched<ICategoryCreateModel>,
    field: keyof ICategoryCreateModel /* "id" | "name" | "image" | "description" */) => {
    return errors[field] && touched[field]
        ? <div className="error-text">{errors[field]}</div>
        : null;
};

type CategoryCUArgs = {
    initialVals: ICategoryCreateModel,
    validationSchema: ObjectSchema<InferType<typeof categoryUpdateSchema>>,
    formikSubmit: (val: ICategoryCreateModel) => Promise<void>,
    initImg?: string,
    updating: boolean,
}

export default function RenderCategoryCUForm({ initialVals, validationSchema, formikSubmit, updating, initImg }: CategoryCUArgs) {
    // Formik component syntax
    return (
        // https://formik.org/docs/guides/validation
        <Formik initialValues={initialVals} enableReinitialize validationSchema={validationSchema} onSubmit={formikSubmit}>
            {
                /*
                 * can be either with or without this function
                 * this arrow function wrapper is used to
                 * catch formik state objects and hooks
                 */
            }
            {({ values, errors, touched, setFieldValue }) => (
                <Form className="mx-auto">
                    <div className="form-group justify-center">
                        <h1>{
                            updating
                                ? `Оновити категорію ${values.name}`
                                : `Додати нову категорію`
                        }</h1>
                    </div>
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
                            <label htmlFor="description">Опис</label>
                        </div>
                        <div className="md:w-10/12">
                            <Field
                                id="description"
                                name="description"
                                type="text"
                                as="textarea"
                                placeholder="Введіть опис..."
                            />
                        </div>
                    </div>
                    {getErrorComponents(errors, touched, "description")}
                    <div className="form-group">

                        <div className="md:w-2/12">
                            <span>Фото</span>
                        </div>
                        <label htmlFor="image" className="md:w-10/12 flex justify-between p-0">
                            <img className="h-16 w-16 object-cover rounded-full inline-block" alt="Current category"
                                src={
                                    values.image instanceof File && !errors.image
                                        ? URL.createObjectURL(values.image)
                                        : (initImg ?? defaultPic)
                                } />
                            <span className="sr-only">Choose a category photo</span>
                            <input type="file" className="file w-5/6" id="image" name="image" onChange={e => {
                                // input stores an array of attachments
                                // but uses only one item unless the multiple mode is enabled

                                // file extracting
                                const files = e.target.files;
                                if (files) {
                                    const file = files[0];
                                    // setting file on form model
                                    setFieldValue("image", file);
                                }
                            }} />
                        </label>
                    </div>
                    {getErrorComponents(errors, touched, "image")}
                    <div className="flex justify-center">
                        <button className="form-button" type="submit">{updating ? `Оновити` : `Додати`}</button>
                    </div>
                </Form>
            )}
        </Formik>
    );
}