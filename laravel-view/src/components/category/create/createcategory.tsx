import React from "react"
import { useNavigate } from "react-router-dom";
import { useFormik } from "formik";

import APP_ENV from "../../../env/app_env";
import ICategoryItem from "../../../models/category";
import http_common from "../../../http_common";

const emptyCategory: ICategoryItem = {
    id: undefined,
    name: '',
    image: '',
    description: '',
}

export default function CreateCategory() {
    const navigate = useNavigate();
    const formikSubmit = async (value: ICategoryItem) => {
        try {
            await http_common.post("api/categories/create", values);
            navigate("/all");
        }
        catch (err) {
            console.error('Server side error. Details: ' + err);
        }
    }
    const formik = useFormik({
        initialValues: emptyCategory,
        onSubmit: formikSubmit,
    });
    const { values, handleSubmit, handleChange } = formik;

    return (
        <>
            <h1>Додати нову категорію</h1>
            <form onSubmit={handleSubmit} className="mx-auto">
                <div className="form-group">
                    <div className="md:w-1/3">
                        <label htmlFor="name">
                            Назва
                        </label>
                    </div>
                    <div className="md:w-2/3">
                        <input id="name" name="name" type="text" placeholder="Введіть назву..."
                            value={values.name} onChange={handleChange} />
                    </div>
                </div>
                <div className="form-group">
                    <div className="md:w-1/3">
                        <label htmlFor="image">
                            Зображення
                        </label>
                    </div>
                    <div className="md:w-2/3">
                        <input id="image" name="image" type="text" placeholder="Введіть адресу зображення..."
                            value={values.image} onChange={handleChange} />
                    </div>
                </div>
                <div className="form-group">
                    <div className="md:w-1/3">
                        <label htmlFor="description">
                            Опис
                        </label>
                    </div>
                    <div className="md:w-2/3">
                        <input id="description" name="description" type="text" placeholder="Введіть опис..."
                            value={values.description} onChange={handleChange} />
                    </div>
                </div>

                <div className="flex justify-center">
                    <button type="submit">
                        Додати
                    </button>
                </div>
            </form>
        </>
    )
}