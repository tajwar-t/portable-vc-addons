jQuery(function ($) {
  $(".portable-posts-grid").each(function () {
    const box = $(this);
    const loader = box.find(".portable-loader");
    const inner = box.find(".portable-posts-grid-inner");
    const loadMoreBtn = box.find(".portable-load-more");
    const loadLessBtn = box.find(".portable-load-less");

    // Hide load less button initially
    loadLessBtn.hide();

    // Store initial state
    const initialItemsCount = inner.find(".portable-post-item").length;
    let currentPage = 1;
    let isLoading = false;

    box.on("click", ".portable-load-more, .portable-load-less", function () {
      if (isLoading) return; // Prevent multiple clicks

      const isMore = $(this).hasClass("portable-load-more");
      const limit = parseInt(box.data("limit"));
      const categories = box.data("categories");

      if (isMore) {
        // Load More Logic
        const nextPage = currentPage + 1;

        isLoading = true;
        loader.show();
        loadMoreBtn.hide();
        loadLessBtn.hide();

        $.post(
          PortablePostsGrid.ajax,
          {
            action: "portable_posts_grid_load",
            page: nextPage,
            limit: limit,
            categories: categories,
          },
          function (response) {
            loader.hide();
            isLoading = false;

            // Parse response
            const data =
              typeof response === "string" ? JSON.parse(response) : response;

            if (data.html && data.html.trim() !== "") {
              // Append new posts
              inner.append(data.html);
              currentPage = nextPage;
              box.data("page", currentPage);

              // Show load less button since we have loaded items beyond initial page
              loadLessBtn.show();

              // Only hide load more if no more posts available
              if (data.has_more === false) {
                loadMoreBtn.hide();
              } else {
                loadMoreBtn.show();
              }
            } else {
              // No more posts to load
              loadMoreBtn.hide();

              // Show load less if we're beyond page 1
              if (currentPage > 1) {
                loadLessBtn.show();
              }
            }
          },
        ).fail(function () {
          loader.hide();
          isLoading = false;
          loadMoreBtn.show();
          if (currentPage > 1) {
            loadLessBtn.show();
          }
        });
      } else {
        // Load Less Logic
        const currentItemsCount = inner.find(".portable-post-item").length;

        // Only remove items if we have more than the initial page load
        if (currentItemsCount > initialItemsCount) {
          // Remove the last batch of items (limit number of items)
          const itemsToRemove = Math.min(
            limit,
            currentItemsCount - initialItemsCount,
          );
          inner.find(".portable-post-item").slice(-itemsToRemove).remove();

          currentPage--;
          box.data("page", currentPage);

          // Always show load more button when removing items (there are more posts available)
          loadMoreBtn.show();

          // Check if we're back to initial page
          const remainingItems = inner.find(".portable-post-item").length;
          if (remainingItems <= initialItemsCount) {
            // Back to initial state, hide load less
            loadLessBtn.hide();
          } else {
            // Still have loaded items beyond initial page
            loadLessBtn.show();
          }
        }
      }
    });
  });
});
