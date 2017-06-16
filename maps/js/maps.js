import _ from 'lodash'

let data = [ 'A', 'B', 'C' ]

let mapped = _.map(data, (d) => {
  return 'Hello' + d
})




async function wait () {
  await new Promise((resolve, reject) => {
    setTimeout(resolve, 500)
  })
  console.log('waited!')
}

wait()

console.log('here')


console.log(mapped)
