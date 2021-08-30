const {Spinner, SelectControl} = wp.components;

const Top = ({state: {init, updating, deleting}, playerType, setPlayerType, updateData, deletePlayer}) => {
    const postID = document.getElementById('post_ID').value;

    return (
        <div className="aiovp-metabox-top">

            <div className="player_shortcode">
                <div className="tooltip-wrap">
                    <span className="tooltip-text">Shortcode</span>
                    <i className="dashicons dashicons-shortcode"></i>
                    <input id="player_shortcode" className="shortcode" type="text" readOnly
                           value={`[aiovp id="${postID}"]`}/>
                </div>
            </div>

            <div className="player_type">
                <SelectControl
                    label={'Player Type : '}
                    value={playerType}
                    options={[
                        {label: 'Playlist', value: 'playlist'},
                        {label: 'Single', value: 'single'},
                    ]}
                    onChange={playerType => setPlayerType(playerType)}
                />
            </div>

            {(init || updating) && <Spinner/>}

            <button type='button'
                    onClick={() => {
                        handleChange({updating: true})
                        window.location = aiovp.admin_url + '/edit.php?post_type=aiovp';
                    }}
                    className="button">
                <div className="tooltip-wrap">
                    <span className="tooltip-text">Back to all players</span>
                    <span className="dashicons dashicons-arrow-left-alt2"></span>
                    {deleting ? 'Deleting...' : 'Back'}
                </div>
            </button>

            {!!window.location.href.match(/post-new.php/) ||
            <button type='button' onClick={() => {
                const yes = confirm('Are you sure, want to delete the player?');

                if (yes) {
                    deletePlayer();
                }

            }} className="button-link-delete button">
                <div className="tooltip-wrap">
                    <span className="tooltip-text">Delete Player</span>
                    <span className="dashicons dashicons-trash"></span>
                    {deleting ? 'Deleting...' : 'Delete'}
                </div>
            </button>
            }

            {/*  Preview  */}
            <button type='button'
                    onClick={() => {
                        updateData();
                        window.open(aiovp.site_url + `?all_in_one_video_player=${postID}&preview`);
                    }}
                    className="button">

                <div className="tooltip-wrap">
                    <span className="tooltip-text">Player Preview</span>
                    <span className="dashicons dashicons-visibility"></span>
                    {updating ? 'Saving...' : 'Preview'}
                </div>

            </button>

            {/* Save changes */}
            <button type='button' onClick={updateData} className="button-primary button">
                <span className="dashicons dashicons-yes"></span>
                {updating ? 'Saving...' : 'Save Changes'}
            </button>

        </div>
    )
}

export default Top;