export default function Uploader({media, onChange, config}) {


    const openUploader = (e) => {
        e.preventDefault();

        // Create the media frame.
        const file_frame = wp.media.frames.file_frame = wp.media({
            title: config.title,
            library: {
                type: config.type
            },
            button: {
                text: config.text,
            },
            multiple: false
        });

        file_frame.on('select', () => {
            const attachment = file_frame.state().get('selection').first().toJSON();
            onChange(attachment);
        });

        // Finally, open the modal
        file_frame.open();
    }

    const deleteMedia = () => {
        onChange('');
    }


    return (
        <>
            <div className="thumbnail-metabox-actions">
                <label htmlFor="">{config.label}</label>

                <div className="form-group">

                    {'image' === config.type && !!media &&
                    <img src={media} className={'media-preview'}/>
                    }

                    <input type="text" value={media} onChange={ e => onChange(e.target.value) }/>
                </div>

                <button type={'button'} className="button button-primary select_video" onClick={openUploader}>
                    <i className="dashicons dashicons-plus-alt"> </i>
                </button>

                {!!media &&
                <button type={'button'} className="button button-link-delete remove_video" onClick={deleteMedia}>
                    <i className="dashicons dashicons-trash"> </i></button>
                }

            </div>

            <p className="description">{config.desc}</p>

        </>
    )

}