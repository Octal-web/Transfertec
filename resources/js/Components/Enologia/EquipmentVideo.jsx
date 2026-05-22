export const EquipmentVideo = ({ }) => {
    return (
        <div className="absolute top-0 bottom-0 left-0 w-full md:w-[43%] max-md:h-[45%]">
            <video src="/eno/site/video/equipment-video.mp4" className="absolute w-full h-full object-cover opacity-60" autoPlay muted loop />

            <div className="absolute inset-0 bg-eno-secondary mix-blend-overlay opacity-50" />
            <div className="absolute inset-0 bg-black opacity-30" />
        </div>
    );
};