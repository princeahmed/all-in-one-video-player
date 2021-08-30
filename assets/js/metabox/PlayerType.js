export default function PlayerType({setPlayerType}) {
    return (
        <div className={'aiovp-metabox-body playertype-selection'}>
            <h3>Player Type</h3>

            <h4>Select the player type to optimize the player for your usage.</h4>

            <div className="player-types">
                <div className="player-type" onClick={() => {
                    setPlayerType('single')
                }}>
                    <i className="dashicons dashicons-media-video"></i>
                    <span>Single Video Player</span>
                </div>

                <div className="player-type" onClick={() => setPlayerType('playlist')}>
                    <i className="dashicons dashicons-playlist-video"></i>
                    <span>Video Playlist Player</span>
                </div>
            </div>

        </div>
    )
}