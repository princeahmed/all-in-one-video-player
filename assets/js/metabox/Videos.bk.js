import classNames from "classnames";

import Uploader from './Uploader'

const {useState} = wp.element;
const {SelectControl, TextControl} = wp.components;

export default function Videos({videos, setVideos, playerType}) {

    videos = !!videos.length ? videos : [{
        title: '',
        source: 'YouTube',
        video: '',
        poster: '',
    }]

    const [active, setActive] = useState(!!videos && videos.length === 1 ? 0 : null);

    const handleChange = (obj) => {
        const key = Object.keys(obj)[0];
        videos[active][key] = obj[key];

        setVideos([...videos]);
    }

    const removeVideo = (index) => {
        const conf = confirm('Are you sure want to delete the track?')

        if (conf) {
            setVideos(videos.filter((item, i) => i !== index));
        }
    }

    if ('single' === playerType) {
        videos = [{...videos[0]}];
    }


    return (
        <div className='content-tab'>
            <h3 className="tab_title">{'single' === playerType ? 'Video' : 'Videos'}</h3>

            <div className="video-items">

                {!!videos && !!videos.length && videos.map(({title, source, video, poster}, index) => {
                    const isActive = index === active;

                    return (
                        <div className={classNames('video-item', {active: isActive})}>

                            {'single' !== playerType &&
                            <div className="video-item-header">
                                <h4>{index + 1}. {title}</h4>

                                {videos.length > 1 &&
                                <button type={"button"} className={'button button-link-delete'}
                                        onClick={() => removeVideo(index)}>
                                    <i className="dashicons dashicons-trash"></i>
                                </button>
                                }

                                <button type={"button"} className={'button button-primary'}
                                        onClick={() => setActive(isActive ? null : index)}>
                                    <i className={classNames('dashicons', {'dashicons-arrow-down-alt2': !isActive}, {'dashicons-arrow-up-alt2': isActive})}></i>
                                </button>

                            </div>
                            }

                            {(isActive || 'single' === playerType) &&
                            <div className="video-item-body">
                                <div className="form-field">
                                    <TextControl
                                        label={'Title'}
                                        value={title}
                                        onChange={title => handleChange({title})}
                                        help={'Enter the video title.'}
                                    />
                                </div>

                                <div className="form-field">

                                    <SelectControl
                                        label={'Source'}
                                        value={source}
                                        options={[
                                            {label: 'YouTube', value: 'YouTube'},
                                            {label: 'SoundCloud', value: 'SoundCloud'},
                                            {label: 'Facebook', value: 'Facebook'},
                                            {label: 'Vimeo', value: 'Vimeo'},
                                            {label: 'Twitch', value: 'Twitch'},
                                            {label: 'Streamable', value: 'Streamable'},
                                            {label: 'Wistia', value: 'Wistia'},
                                            {label: 'DailyMotion', value: 'DailyMotion'},
                                            {label: 'Mixcloud', value: 'Mixcloud'},
                                            {label: 'Vidyard', value: 'Vidyard'},
                                            {label: 'Self Hosted', value: 'SelfHosted'},
                                        ]}
                                        onChange={source => handleChange({source})}
                                        help={'Select video source you want to use.'}
                                    />
                                </div>

                                <div className="form-field">
                                    {'SelfHosted' === source ?
                                        <Uploader
                                            media={video}
                                            onChange={media => handleChange({video: media.url})}
                                            config={{
                                                title: 'Select Video',
                                                type: 'video',
                                                text: 'Use this video',
                                                label: 'Select Video',
                                                desc: 'Enter the self hosted video url or select an video from media library.'
                                            }}
                                        />
                                        :
                                        <TextControl
                                            label={'Video URL'}
                                            value={video}
                                            onChange={video => handleChange({video})}
                                            help={'Enter the video URL.'}
                                        />
                                    }
                                </div>

                                <div className={'form-field'}>
                                    <Uploader
                                        media={poster}
                                        onChange={media => handleChange({poster: media.url})}
                                        config={{
                                            title: 'Select Image',
                                            type: 'image',
                                            text: 'Use this image',
                                            label: 'Poster Image',
                                            desc: 'Enter the image url or select an image from media library.'
                                        }}
                                    />
                                </div>

                            </div>
                            }
                        </div>
                    )
                })}


                {'single' !== playerType &&
                <div className="add-new-btn-wrap">
                    <button type={"button"} className={classNames('add-new button')}
                            onClick={() => {
                                setVideos([...videos, {}]);
                                setActive(videos.length);
                            }}>
                        <i className="dashicons dashicons-plus-alt"></i>
                        <span>Add New Video</span>
                    </button>
                </div>
                }

            </div>
        </div>
    )

}

