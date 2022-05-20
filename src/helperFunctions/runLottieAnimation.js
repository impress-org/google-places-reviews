import lottie from 'lottie-web';


export default function runLottieAnimation(animationName, containerId, loop = true, autoplay = true) {

    import(`../lotties/${animationName}.json`).then((animationData) => {
        const animation = lottie.loadAnimation({
            container: document.getElementById(containerId),
            loop: loop,
            autoplay: autoplay,
            animationData: animationData,
        });

    });
}
