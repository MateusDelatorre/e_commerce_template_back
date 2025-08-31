package com.example.demo.domain.entities.product;

import java.util.List;

public interface IProduct {
    Long getId();
    String getDescription();
    String getName();
    Double getPrice();
    Integer getStockQuantity();
    String getCategory();
    String getImageUrl();
    Boolean isActive();
    List<String> getTags();
    List<String> getReviews();
    Double getAverageRating();
    List<String> getOptions();
}
