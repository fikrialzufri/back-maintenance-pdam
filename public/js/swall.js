function deleteconf(id) {
    const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false,
    });

    swalWithBootstrapButtons({
        title: "Anda Yakin ?",
        text: "Hapus Data",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data!",
        cancelButtonText: "Tidak, kembali!",
    }).then((result) => {
        if (result.value) {
            swalWithBootstrapButtons(
                "Menghapus!",
                "data anda telah dihapus.",
                "success"
            );
            document.getElementById("form-" + id).submit();
        } else if (
            // Read more about handling dismissals
            result.dismiss === swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons(
                "Kembali",
                "Mohon berhati-hati untuk mengapus data",
                "error"
            );
        }
    });
}

function luluscon(id) {
    const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false,
    });

    swalWithBootstrapButtons({
        title: "Anda Yakin ?",
        text: "Mengubah ",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Ya, Mengubah!",
        cancelButtonText: "Tidak, kembali!",
    }).then((result) => {
        if (result.value) {
            swalWithBootstrapButtons(
                "question!",
                "Mohon berhati-hati untuk mengubah data",
                "question"
            );
            document.getElementById("form-" + id).submit();
        } else if (
            // Read more about handling dismissals
            result.dismiss === swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons(
                "Kembali",
                "Mohon berhati-hati untuk Mengubah data",
                "error"
            );
        }
    });
}
