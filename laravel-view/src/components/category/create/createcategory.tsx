import React from "react"
import { useNavigate } from "react-router-dom";
import { useFormik, Formik, Form, Field } from "formik";

import ICategoryItem, { categorySchema } from "../../../models/category";
import http_common from "../../../http_common";

const emptyCategory: ICategoryItem = {
    id: undefined,
    name: '',
    image: '',
    description: '',
}

export default function CreateCategory() {
    const navigate = useNavigate();

    const formikSubmit = async (val: ICategoryItem) => {
        try {
            const validatedVal = await categorySchema.validate(val);
            await http_common.post("api/categories/create", validatedVal);
            navigate("/all");
        }
        catch (err) {
            console.error('Server side error. Details: ' + err);
        }
    }

    // https://formik.org/docs/guides/validation
    // TODO: Add validation errors, test them

    return (
        <Formik initialValues={emptyCategory} validationSchema={categorySchema} onSubmit={formikSubmit}>
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
            <div className="flex justify-center">
              <button type="submit">Додати</button>
            </div>
          </Form>
        </Formik>
      );
}