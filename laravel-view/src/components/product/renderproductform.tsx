import React from "react";

import { Formik, Form, Field, FormikErrors, FormikTouched } from "formik";
import { ObjectSchema } from "yup";

import { IProductCreateModel } from "../../models/product";
import { ICategoryReadModel } from "../../models/category";

// for error displaying
const getErrorComponents = (
    errors: FormikErrors<IProductCreateModel>,
    touched: FormikTouched<IProductCreateModel>,
    field: keyof IProductCreateModel /* "id" | "name" | "image" | "description" */) => {
    return errors[field] && ((field !== "images" && touched[field]) || field === "images")
        ? <div className="error-text">{errors[field]}</div>
        : null;
};

type ProductCUArgs = {
    initialVals: IProductCreateModel,
    validationSchema: ObjectSchema<IProductCreateModel>,
    formikSubmit: (val: IProductCreateModel) => Promise<void>,
    updating: boolean,
    categories: ICategoryReadModel[]
}
export default function RenderProductCUForm({ initialVals, validationSchema, formikSubmit, updating, categories }: ProductCUArgs) {
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
                                ? `Оновити продукт ${values.name}`
                                : `Додати новий продукт`
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
                            <label htmlFor="description">Ціна</label>
                        </div>
                        <div className="md:w-10/12">
                            <Field
                                id="price"
                                name="price"
                                type="number"
                                placeholder="Вкажіть ціну..."
                            />
                        </div>
                    </div>
                    {getErrorComponents(errors, touched, "price")}
                    <div className="form-group">
                        <div className="md:w-2/12">
                            <label htmlFor="description">Категорія</label>
                        </div>
                        <div className="md:w-10/12">
                            <Field
                                id="category_id"
                                name="category_id"
                                as="select"
                                placeholder="Введіть опис...">
                                {categories.map((cat, i) => <option key={i} value={cat.id!}>{cat.name}</option>)}
                            </Field>
                        </div>
                    </div>
                    {getErrorComponents(errors, touched, "category_id")}
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
                            <label htmlFor="images">Фото</label>
                        </div>
                        <div className="md:w-10/12 flex justify-between p-0">
                            <input type="file" className="file" id="images" name="images" multiple onChange={e => {
                                // input stores an array of attachments
                                // but uses only one item unless the multiple mode is enabled

                                // file extracting
                                const files = e.target.files;
                                if (files) {
                                    setFieldValue(e.target.name, files);
                                }
                            }} />
                        </div>
                    </div>
                    {getErrorComponents(errors, touched, "images")}
                    <div className="form-group">
                        <ul className="flex flex-row attached-images">
                            {
                                /**
                                 * TODO: 
                                 * Add mapping from field 'images' to the visible 
                                 * FormImage components that will have delete button
                                 */
                            }
                            {
                                values.images instanceof FileList
                                && ([...values.images])?.map((value, index) => {
                                    return <li className="mr-4" key={index}><img src={URL.createObjectURL(value)} alt={value.name} /></li>;
                                })
                            }
                        </ul>
                    </div>
                    <div className="flex justify-center">
                        <button type="submit">{updating ? `Оновити` : `Додати`}</button>
                    </div>
                </Form>
            )}
        </Formik>
    );
}