<template>
    <!-- Vue component template -->
    <div v-if="applicationEdit">
        <button @click="addFileUpload()">Add File</button>
        <div v-for="(file, index) in files" :key="index">
            <input v-if="canEditDocument[index]" accept="application/pdf" type="file"
                   v-bind:disabled="!file.description" @change="onFileChange($event, index)">
            <input v-model="file.description" placeholder="Description" type="text"
                   v-bind:disabled="!canEditDocument[index]">
            <button @click="previewFile($event,index)">preview {{ file.id }}</button>
            <button v-if="file.id !== 0" v-on:click="file.status = 'to delete'">delete {{ file.id }}</button>
            <button v-else @click="files.splice(index, 1);">cancel adding file</button>
            <message v-bind="file.result"></message>
        </div>
    </div>
    <div v-else>
        <div v-for="(file, index) in files" :key="index">
            <label> {{ file.description }} </label>
            <button @click="previewFile($event,index)">preview {{ file.id }}</button>
            <message v-bind="file.result"></message>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        applicationEdit: Boolean,
        cardApplication: Number,
    },
    data() {
        return {
            docFiles: [],
            files: [],
            docLink: '',
        }
    },
    computed: {
        canEditDocument: function () {
            return this.files.map((file, index) => {
                return [null, 'to delete',
                    this.$enums.CardDocumentStatusEnum.INCOMPLETE,
                    this.$enums.CardDocumentStatusEnum.SUBMITTED
                ].includes(file.status)
            });

        },
    },
    methods: {
        startingData() {

        },
        getDocuments() {
            let vue = this;
            let url = route('document.index', {'cardApplication': this.cardApplication});

            axios.get(url)
                .then(function (responseJson) {
                    let json = responseJson['data'];
                    vue.docFiles = json;
                    json.forEach((file, index) => {
                        vue.addFileUpload(null, file.status, file.description, file.id, route('document.show', {
                            'document': file.id
                        }));
                    });
                    if (0 == json.length)
                        vue.addFileUpload();
                    console.log(vue.files);
                })
                .catch(function (errors) {
                    vue.result.success = false;
                    vue.result.message = 'Retrieving files of this application has failed :'
                    vue.result.errors = errors.response.data.errors;
                })

        },
        addFileUpload(file = null, status = null, description = '', id = 0, link = '', message = '', success = null) {
            if (id === 0)
                this.docFiles.push({
                    id: 0,
                    status: status,
                    description: description,
                })

            return this.files.push({
                file: file,
                id: id,
                description: description,
                link: link,
                status: status,
                result: {
                    message: message + status,
                    success: success,
                    hide: false,
                    errors: []
                },
            });
        },
        onFileChange(event, index) {
            let file = this.files[index];
            file.file = event.target.files[0];
            if (!file.file || !file.description)
                return file.message = 'There isn\'t any file or description';

            if (0 == file.id) {
                this.docFiles[index].status = file.status;
                this.docFiles[index].description = file.description;
                return;
            }
            file.id = 0;
            this.files.push(file); // Add the new file
            this.docFiles.push(file); // Add the new file
            let oldFile = this.files[index] = this.docFiles[index]; // Restore the old file
            oldFile.status = (!confirm('Would you like to keep the old file? If yes, you will see the new file at the end')) ? "to delete" : (oldFile.status !== 'incomplete') ? oldFile.status : 'submitted';
        },
        async fileUpload(file, index) {
            let params = new FormData();
            let url;
            let vue = this;
            file.result.message = ''; //#todo more clever way to show if the value is the same
            //is it need to delete the file;
            if (this.$enums.CardDocumentStatusEnum.INCOMPLETE === file.status) {
                file.result.message = 'This file is incomplete. You must submit or replace it.';
                return file.result.success = false;
            }
            // Set URL based on file status
            if ('to delete' === file.status) {
                url = route('document.destroy', {'document': file.id});
                params.append('_method', 'DELETE');
            } else if (0 === file.id) { // Submit a new file
                if (file.file != null) {
                    params.append(`file`, file.file);
                    params.append(`description`, file.description);
                    url = route('document.store', {'cardApplication': this.cardApplication});
                } else {
                    file.result.message = 'There is no file to upload.';
                    return file.result.success = true;
                }
            } else if (file.description != this.docFiles[index].description) { //update existing file description
                url = route('document.update', {'document': file.id});
                params.append(`description`, file.description);
                params.append('_method', 'PUT');
            } else { // File exists but nothing changed
                file.result.message = 'The file has already been uploaded.';
                return file.result.success = true;
            }
            // Check if file can be edited
            if (!this.canEditDocument[index] || !this.applicationEdit) {
                file.result.message = "File can't be edited.";
                return file.result.success = false;
            }
            // Make axios request to upload file
            return await axios.post(url, params)
                .then(function (responseJson) {
                    let json = responseJson['data'];
                    file.id = json['id'];
                    file.result.success = json['success'];
                    file.result.message = json['message'];
                    file.result.errors = [];
                })
                .catch(function (errors) {
                    file.result.errors = errors.response.data.errors;
                    file.result.message = "Request failed:";
                    file.result.message += file.status + ' not uploaded';
                    return file.result.success = false;
                })
                .finally(function () {
                    file.link = '';
                    if (file.result.success) {
                        if (file.status === 'to delete') {
                            let time = 3000;
                            setTimeout(() => {
                                vue.files.splice(index, 1);
                                vue.docFiles.splice(index, 1);
                            }, time);
                            file.result.message += " and the file will be removed from the page in " + time + 'ms';
                        }
                        vue.docFiles[index].status = file.status = ('to delete' === file.status) ? 'deleted' : 'submitted';
                        vue.docFiles[index].description = file.description;
                        vue.docFiles[index].id = file.id;
                    }
                    file.result.message += file.status + (!file.result.success) ? '' : ' not uploaded';
                    return file.result.success;
                });
        },
        previewFile(event, index) {
            const file = this.files[index];
            if ('' !== file.link) return (this.docLink = file.link);
            if (null === file.file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.docLink = file.link = e.target.result;
            };
            reader.readAsDataURL(file.file);
        },
        async submitFiles() {
            let postPromises = [];
            this.files.forEach((file, index) => {
                postPromises.push(this.fileUpload(file, index));
            });
            let result = await Promise.all(postPromises);
            return !result.includes(false);
        }
    },
    watch: {
        cardApplication(newValue) {
            this.getDocuments();
        },
        docLink(newValue) {
            this.$emit('previewFile', newValue)
        }
    },
    created() {
        if (this.cardApplication) this.getDocuments();
    }
}
</script>
