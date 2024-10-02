document.addEventListener('DOMContentLoaded', () => {
    const dobInput = document.getElementById('dob')
    const ageInput = document.getElementById('age')
    const userForm = document.getElementById('userForm')
    const errorMessages = document.getElementById('errorMessages')

    // Calculate age based on date of birth
    dobInput.addEventListener('change', () => {
        const dob = new Date(dobInput.value)
        const today = new Date()
        let age = today.getFullYear() - dob.getFullYear()
        const m = today.getMonth() - dob.getMonth()
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--
        }
        ageInput.value = age
    })

    userForm.addEventListener('submit', (event) => {
        event.preventDefault() // Prevent form submission

        // Clear previous error messages
        while (errorMessages.firstChild) {
            errorMessages.removeChild(errorMessages.firstChild)
        }
        let errors = []

        // Validate Full Name
        const fullName = document.getElementById('fullName').value.trim()
        if (!fullName || !/^[a-zA-Z\s,.'-]+$/.test(fullName)) {
            errors.push(
                'Full Name must contain only letters, spaces, commas, periods, or apostrophes.'
            )
        }

        // Validate Email Address
        const email = document.getElementById('email').value.trim()
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        if (!email || !emailRegex.test(email)) {
            errors.push('Please enter a valid email address.')
        }

        // Validate Mobile Number
        const mobile = document.getElementById('mobile').value.trim()
        const mobileRegex = /^09[0-9]{9}$/
        if (!mobile || !mobileRegex.test(mobile)) {
            errors.push('Mobile Number must be in the format 09171234567.')
        }

        // Validate Gender
        const gender = document.getElementById('gender').value
        if (!gender) {
            errors.push('Please select your gender.')
        }

        // Check for any errors
        if (errors.length > 0) {
            errors.forEach((error) => {
                const errorMessage = document.createElement('div')
                errorMessage.textContent = error
                errorMessage.className = 'text-red-600'
                errorMessages.appendChild(errorMessage)
            })

            errorMessages.classList.remove('hidden')
        } else {
            // If no errors, proceed with AJAX submission
            errorMessages.classList.add('hidden')
            submitForm()
        }
    })

    async function submitForm() {
        const formData = new FormData(userForm)

        try {
            const response = await fetch('submit.php', {
                method: 'POST',
                body: formData,
            })

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`)
            }

            const data = await response.json()

            // Handle the response from the server
            if (data.success) {
                alert('Form submitted successfully!')
                userForm.reset()
                ageInput.value = ''

                while (errorMessages.firstChild) {
                    errorMessages.removeChild(errorMessages.firstChild)
                }
            } else {
                errorMessages.classList.remove('hidden')
                for (const [key, value] of Object.entries(data.errors)) {
                    const errorMessage = document.createElement('div')
                    errorMessage.textContent = value // Use the error message from the server
                    errorMessages.appendChild(errorMessage)
                }
            }
        } catch (error) {
            console.error('Error:', error)
            alert('There was an error submitting the form.')
        }
    }
})
