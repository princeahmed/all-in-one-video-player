import {updateURL} from "../components/functions";
import Top from './Top';
import Videos from "./Videos";
import PlayerType from "./PlayerType";
import classNames from "classnames";

const {useState, useEffect} = wp.element;
const {Spinner} = wp.components;

const Metabox = ({editLink}) => {
    const postID = document.getElementById('post_ID').value;

    const [tab, setTab] = useState('videos');
    const [videos, setVideos] = useState([]);
    const [skin, setSkin] = useState('skin1');
    const [customization, setCustomization] = useState({});
    const [playerType, setPlayerType] = useState(null);
    const [playerTitle, setPlayerTitle] = useState(!!document.getElementById('title').value ? document.getElementById('title').value : 'Player Title');

    const [state, setState] = useState({
        init: true,
        updating: false,
        deleting: false,
    });

    const updateData = () => {

        setState({...state, updating: true})

        wp.apiFetch({
            method: 'POST',
            path: `aiovp/v1/player-data/${postID}`,
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': aiovp.nonce
            },

            data: {videos, postID, playerType, playerTitle}

        }).then(() => {
            setState({...state, updating: false});
            updateURL(editLink);
        });
    }

    const getData = () => {

        wp.apiFetch({
            path: `aiovp/v1/player-data/${postID}`
        }).then(({data: {videos, playerType, playerTitle}}) => {

            if (!!videos) {
                setVideos([...videos]);
            }

            setPlayerType(playerType);
            setPlayerTitle(playerTitle);

            setState({...state, init: false});
        });
    }

    useEffect(() => {
        getData();


        document.getElementById('title').oninput = (e) => {
            setPlayerTitle(e.target.value)
            jQuery(window).off('beforeunload');
        }
    }, []);


    return (
        <div className={classNames('aiovp-metabox', `player-type-${playerType}`)}>

            {state.init ? <Spinner/> :
                <>
                    {!!playerType || <PlayerType setPlayerType={setPlayerType}/>}

                    {!!playerType &&
                    <>
                        <Top playerType={playerType} setPlayerType={setPlayerType} updateData={updateData}
                             state={state}/>

                        {/* sidebar tabs */}
                        <div className="aiovp-metabox-sidebar">

                            <button type={'button'} className={'videos' === tab && 'active'}
                                    onClick={() => setTab('videos')}>
                                <i className={`dashicons dashicons-${'single' === playerType ? 'media' : 'playlist'}-video`}></i>
                                {'single' === playerType ? 'Video' : 'Videos'}
                            </button>

                            <button type={'button'} className={'skin' === tab && 'active'}
                                    onClick={() => setTab('skin')}>
                                <i className="dashicons dashicons-format-video"></i> Skin
                            </button>

                            <button type={'button'} className={'customization' === tab && 'active'}
                                    onClick={() => setTab('customization')}>
                                <i className="dashicons dashicons-admin-appearance"></i> Customization
                            </button>

                        </div>

                        {/* metabox body */}
                        <div className="aiovp-metabox-body">
                            {'videos' === tab &&
                            <Videos
                                videos={videos}
                                setVideos={setVideos}
                                playerType={playerType}
                            />
                            }
                        </div>
                    </>
                    }
                </>
            }
        </div>
    )

}


const app = document.getElementById('aiovp_metabox_app');

if (app) {
    const editLink = app.getAttribute('data-edit_link');

    wp.element.render(<Metabox editLink={editLink}/>, app);
}