import classNames from "classnames";
import Icons from "../components/Icons";

const {FormToggle} = wp.components;

export default function Playlist({
                                     videos,
                                     videoIndex,
                                     setVideoIndex,
                                     setPlaying,
                                     autoplay,
                                     setAutoplay,
                                     setShowPlaylist,
                                 }) {

    const skin = 1;

    return (
        <div className="aiovp-playlist">

            <div className="playlist-controls">
                <div className="playlist-controls__autoplay">
                    <span>Autoplay : </span>
                    <FormToggle
                        checked={autoplay}
                        onChange={() => {
                            setAutoplay(!autoplay)
                        }}
                    />
                </div>

                {2 === skin &&
                <button type={'button'}
                        onClick={() => setShowPlaylist(!setShowPlaylist)}
                        dangerouslySetInnerHTML={{__html: Icons('playlist')}}></button>}

            </div>

            <div className="playlist-items">
                {videos.map(({title, poster}, index) => {

                    poster = !!poster ? poster : aiovp.plugin_url + '/assets/images/thumbnail.svg';

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
        </div>
    )
}