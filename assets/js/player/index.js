import Player from "./Player";

export function aiovpInit() {
    document.querySelectorAll('.all-in-one-video-player').forEach(function (element) {

        const id = element.getAttribute('data-id');
        let videos = element.getAttribute('data-videos');

        videos = videos ? JSON.parse(videos) : [];

        wp.element.render(
            <Player
                data={{
                    id,
                    videos,
                }}
            />,
            element
        )

    });
}

aiovpInit();