export default {
    data() {
        return {
            school: null,
            schools: [],
        }
    },
    methods: {
        showSchools(){
            if(this.school.length > 3 && this.school !== ' '){
                axios.get('/schools/show_all_schools', {params: {escuela: this.school}}).then(response => {
                    this.schools = response.data;
                }).catch(error => {
                    // PENDIENTE
                });
            } else {
                this.schools = [];
            }
        },
        showSchoolsActive(){
            if(this.school.length > 3 && this.school !== ' '){
                axios.get('/schools/show_schools', {params: {escuela: this.school}}).then(response => {
                    this.schools = response.data;
                }).catch(error => {
                    // PENDIENTE
                });
            } else {
                this.schools = [];
            }
        }
    },
}