<x-admin-layout>
    <a class="btn btn-danger mt-5 px-5 ml-5" href="javascript:;" id="share-button">Share Now</a><script>
       const shareFiles = async () => {
    const files = [];

    @foreach ($filePaths as $filePath)
        const response{{$loop->iteration}} = await fetch('{{ $filePath }}');
        const blob{{$loop->iteration}} = await response{{$loop->iteration}}.blob();
        const file{{$loop->iteration}} = new File([blob{{$loop->iteration}}], '{{ basename($filePath) }}', { type: 'application/pdf' });
        files.push(file{{$loop->iteration}});
    @endforeach

    try {
        if (navigator.canShare && navigator.canShare({ files })) {
            await navigator.share({
                files,
                title: 'Share these files on WhatsApp',
                text: 'Check out these files!'
            });
            console.log('Files shared automatically');
        } else {
            const whatsappLink = 'whatsapp://send?text=Check out these files: ' +
                files.map(file => encodeURIComponent(file.name)).join(', ') +
                '&phone={{ urlencode($phoneNumber) }}';
            window.location.href = whatsappLink;
        }
    } catch (error) {
        console.error('Error sharing files:', error);
    }
};

document.getElementById('share-button').addEventListener('click', shareFiles);

    </script></x-admin-layout>