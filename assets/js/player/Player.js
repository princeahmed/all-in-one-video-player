import ReactPlayer from "react-player";
import classNames from "classnames";
import Icons from '../components/Icons';


const {useState, useEffect, useRef} = wp.element;

export default function Player({data}) {
    let {id, videos, autoplay} = data;

    const getVolume = () => {
        let volume = (aiovp.settings.volume / 100);

        if (!!localStorage.getItem('aiovp_volume')) {
            volume = parseFloat(localStorage.getItem('aiovp_volume'));
        }

        if (!volume) volume = .8;

        return volume;
    }

    const playerRef = useRef();

    const [playing, setPlaying] = useState(false);
    const [buffering, setBuffering] = useState(false);
    const [volume, setVolume] = useState(getVolume());
    const [error, setError] = useState(null);
    const [hide, setHide] = useState(!!sessionStorage.getItem('radio_player_hide'));
    const [videoIndex, setVideoIndex] = useState(0);
    const [showPlaylist, setShowPlaylist] = useState(false);
    const [pip, setPip] = useState(false);

    let {video} = videos[videoIndex >= videos.length ? 0 : videoIndex];


    /**
     * Init play
     */
    useEffect(() => {

        if (!!aiovp.is_admin) return;

        const playingID = sessionStorage.getItem('aiovp_playing');

        if (playingID == id || (!!playingID && 'on' === autoplay)) {
            setPlaying(true);
        }

    }, []);

    // Handle play-pause
    const handlePlayPause = (isPlaying = playing) => {
        setPlaying(!isPlaying);
    }

    /**
     * Handle volume
     *
     * @param e
     */
    function handleVolume(e) {
        const val = e.target.value;
        setVolume(val);
        localStorage.setItem('aiovp_volume', val)
    }

    const isPro = true;

    const handleNextPrev = (type) => {
        if ('prev' === type) {
            let index = videoIndex - 1;

            if (videoIndex === 0) {
                index = (videos.length - 1);
            }

            setVideoIndex(index);

        } else if ('next' === type) {
            let index = videoIndex + 1;

            if (videoIndex >= (videos.length - 1)) {
                index = 0;
            }

            setVideoIndex(index);
        }

        handlePlayPause(false);
    }

    const handlePip = () => {
        setPip(!pip);
    }

    return (
        <>
            <div className={classNames(`aiovp`, {pip})}>

                <div className="aiovp-controls">
                    <button type={'button'}
                            onClick={() => handleNextPrev('prev')}
                            dangerouslySetInnerHTML={{__html: Icons('prev')}}
                    ></button>
                    <button type={'button'}
                            onClick={handlePlayPause}
                            dangerouslySetInnerHTML={{__html: playing ? Icons('pause') : Icons('play')}}
                    ></button>
                    <button type={'button'}
                            onClick={() => handleNextPrev('next')}
                            dangerouslySetInnerHTML={{__html: Icons('next')}}
                    ></button>

                    <button type={'button'}
                            onClick={handlePip}
                            dangerouslySetInnerHTML={{__html: Icons('pip')}}
                    ></button>

                </div>

                <ReactPlayer
                    className="aiovp_media"
                    ref={playerRef}
                    volume={volume}
                    playing={playing}
                    pip={pip}
                    url={video}
                    controls={true}
                    light={true}
                    // config={{
                    //     youtube: {
                    //         playerVars: {
                    //             controls: 1
                    //         }
                    //     },
                    // }}
                    onPlay={() => {
                        setBuffering(false);
                        setError(false);
                    }}

                    onPause={() => {
                    }}

                    onError={() => {
                        setPlaying(false);
                        setError(true);
                        setBuffering(false);
                    }}

                    onBuffer={() => {
                        setBuffering(true);
                    }}

                    onBufferEnd={() => {
                        setBuffering(false);
                    }}

                    onEnded={() => {
                        setPlaying(false);
                        setBuffering(false);
                    }}

                />

            </div>

        </>
    )


}