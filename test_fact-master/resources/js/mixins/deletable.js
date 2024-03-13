export const deletable = {
    methods: {
        cancel(url) {
            return new Promise((resolve) => {
                this.$confirm('¿Desea eliminar anular?', 'Anular', {
                    confirmButtonText: 'Anular',
                    cancelButtonText: 'Cerrar',
                    type: 'warning'
                }).then(() => {
                    this.$http.post(url)
                        .then(res => {
                            if(res.data.success) {
                                this.$message.success('Se anuló correctamente el registro')
                                resolve()
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar anular');
                            } else {
                                console.log(error.response.data.message)
                            }
                        })
                }).catch(error => {
                    console.log(error)
                });
            })
        },
        destroy(url) {
            return new Promise((resolve) => {
                this.$confirm('¿Desea eliminar el registro?', 'Eliminar', {
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {
                    this.$http.delete(url)
                        .then(res => {
                            if(res.data.success) {
                                this.$message.success('Se eliminó correctamente el registro')
                                resolve()
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar eliminar');
                            } else {
                                console.log(error.response.data.message)
                            }
                        })
                }).catch(error => {
                    console.log(error)
                });
            })
        },
    }
}
