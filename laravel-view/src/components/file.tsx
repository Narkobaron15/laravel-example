import React from 'react';
import { FormikErrors } from 'formik';
import { IProductCreateModel } from '../models/product';
import { IApiImage } from '../models/common';

type FilesArgs = {
    files?: File[] | null | undefined,
    setFilesCallback?: (value: File[]) => Promise<void | FormikErrors<IProductCreateModel>>,
    current_files?: IApiImage[],
    removeCurrentCallback?: (picture: IApiImage) => void,
}
type FileArgs = {
    src: string,
    name: string,
    delfunc: () => void,
}

export default function FilesComponent({ files, setFilesCallback, current_files, removeCurrentCallback }: FilesArgs) {
    return (
        <div className="form-group justify-evenly flex-wrap">
            {current_files?.map(file => (
                <FileComponent key={file.name}
                    src={file.xs}
                    name={file.name}
                    delfunc={() => removeCurrentCallback?.call({}, file)} />
            ))}
            {[...files ?? []].map(file => (
                <FileComponent key={file.name}
                    src={URL.createObjectURL(file)}
                    name={file.name}
                    delfunc={() => setFilesCallback?.call({}, files?.filter(f => f !== file) ?? [])} />
            ))}
        </div>
    )
}

export function FileComponent({ src, name, delfunc }: FileArgs) {
    return (
        <div className='file'>
            <img src={src} alt='' />
            <span className="ml-5 self-center">{name}</span>
            <button type='button' className="p-0 bg-transparent" onClick={delfunc}>
                <i className="fa-solid fa-xmark"></i>
            </button>
        </div>
    )
}