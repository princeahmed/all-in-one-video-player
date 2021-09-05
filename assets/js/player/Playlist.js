import classNames from "classnames";

const {FormToggle} = wp.components;

export default function Playlist({videos, videoIndex, setVideoIndex, setPlaying, autoplay, setAutoplay}) {
    return (
        <div className="aiovp-playlist">

            <div className="playlist-controls">
                <div className="playlist-controls__autoplay">
                    <span>Autoplay : </span>
                    <FormToggle
                        checked={autoplay}
                        onChnage={() => setAutoplay(!autoplay)}
                    />
                </div>
            </div>

            {videos.map(({title, poster}, index) => {

                return (
                    <div className={classNames('playlist-item', {active: videoIndex === index})}
                         onClick={() => {
                             setVideoIndex(index);
                             setPlaying(true);
                         }}>

                        <img src={poster} alt={title}/>
                        <span>{title}</span>
                    </div>
                )
            })
            }
        </div>
    )
}