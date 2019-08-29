import Macy from 'macy'

const MacyGrid = {
  init: function () {
    const macyGrids = document.querySelectorAll('.js-macy')
    Array.from(macyGrids).forEach(grid => {
      let macy = Macy({
        container: grid,
        margin: 15,
        columns: 3,
        breakAt: {
          767: 2,
          575: 1
        }
      })
    })
  }
}

MacyGrid.init()
