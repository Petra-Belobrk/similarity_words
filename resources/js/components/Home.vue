<template>
    <div class="mt-2">
       <h1>Check the similarities between two sentences!</h1>
       <div class="row">
       <div class="form-group col-md-6">
        <label for="userInput">Enter two sentences, divided  with .</label>
        <textarea v-model="userInput.sentences" 
                class="form-control" 
                name="userInput"  
                rows="4" cols="50" 
                :class="[{'is-invalid': errorFor('sentences')}]"></textarea> 
        <div class="invalid-feedback" v-for="error in errors" :key="error.id">{{ error }}</div>
       </div>
       <div v-if="successfulUserData" class="col-md-6">
           <p v-html="firstSentance"></p>
           <p>{{secondSentance}}</p>
           <p>{{percentage}} %</p>
       </div>
       </div>
       <div class="row">
        <div class="form-group col-md-6">
        <button @click="check" class="btn btn-primary">Check!</button>
       </div>
       </div>
       <hr>
       <div class="row">
      <div class="form-group col-md-6">
        <h3>Or choose two random sentences</h3>
       <div class="justify-content-md-center">
           <label class="px-2" for="bacon">Give me bacon!</label>
           <input type="radio" name="picked" value="Bacon" v-model="picked">
           <br>
           <label class="px-2" for="pirate">Yarr pirate me!</label>
           <input type="radio" name="picked" value="Pirate" v-model="picked">
       </div>
        <button class="btn btn-primary" @click="random">Randomize!</button>
       </div>
       <div v-if="successfulData" class="col-md-6">
           <p v-html="firstSentance"></p>
           <p>{{secondSentance}}</p>
           <p>{{percentage}} %</p>
       </div>
       </div>

    </div>
</template>
<script>
export default {
    data() {
        return {
            errors: null,
            percentage: 0,
            userInput: {
                sentences: null
            },
            picked: null,
            firstSentance: null,
            secondSentance: null,
            successfulData: false,
            successfulUserData: false,
        }
    },
    methods: {
        async random() {
            this.errors = null
           try {
            let { data } = await axios.post(
                        `/api/calculate`, {picked: this.picked}
                    )
            this.successfulData = true
            this.successfulUserData = false
            this.percentage = data.percentage
            this.firstSentance = data.firstSentance
            this.secondSentance = data.secondSentance
            }
           catch(error) {
               console.log(error)
               this.errors = error.response && error.response.data.errors;
           }
        },
        errorFor(field) {
            return null !== this.errors && this.errors[field]
                ? this.errors[field]
                : null;
        },
        async check() {
            this.errors = null;
            try {
            axios.post(`/api/userInput`, this.userInput).then(response => {
            this.successfulUserData = true
            this.successfulData = false
            this.percentage = response.data.percentage
            this.firstSentance = response.data.firstSentance
            this.secondSentance = response.data.secondSentance
            this.userInput = ''
            }).catch(error => {
                this.errors = error.response && error.response.data.errors;
            })
            }
            catch(error) {
                this.errors = error.response && error.response.data.errors;
            }
        }
    },
}
</script>