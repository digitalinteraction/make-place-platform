// import _ from 'lodash'

async function wait () {
  await new Promise((resolve, reject) => {
    setTimeout(resolve, 500)
  })
  console.log('waited!')
}

wait()
