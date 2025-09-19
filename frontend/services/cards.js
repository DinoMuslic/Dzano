function renderGigs(gigs) {
  const $gigsContainer = $(".gigsContainer");
  $gigsContainer.empty();

  const BASE_URL = Utils.get_base_url();

  $.each(gigs, function(index, gig) {
    const $col = $("<div class='col-md-3 mb-4 d-flex justify-content-center'></div>");
    const $gigCard = $("<div class='card gig-card'></div>");

    // ✅ Dynamically set gig image
    const imageUrl = gig.gig_image_url ? BASE_URL + gig.gig_image_url : 'assets/freelance.jpg';
    const $gigImage = $(`<img class='card-img-top' alt='Gig Image' src='${imageUrl}'>`);

    const $cardBody = $("<div class='card-body d-flex flex-column'></div>");
    const $gigTitle = $("<h5 class='card-title'></h5>").text(gig.title);
    const $gigDescription = $("<p class='card-text flex-grow-1'></p>").text(gig.description);

    // ✅ Price + Button at bottom, stacked
    const $cardFooter = $("<div class='card-footer'></div>");
    const $gigPrice = $("<p class='card-text fw-bold mb-0'></p>").text("Price: " + gig.price + " $");
    const $gigButton = $(`<a href="#single-gig" class="btn btn-success">View Details</a>`).click(() => {
      sessionStorage.setItem('bf_current_gig_id', String(gig.id));
    });

    $cardFooter.append($gigPrice, $gigButton);
    $cardBody.append($gigTitle, $gigDescription, $cardFooter);
    $gigCard.append($gigImage, $cardBody);
    $col.append($gigCard);
    $gigsContainer.append($col);
  });
}
